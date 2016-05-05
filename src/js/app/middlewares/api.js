'use strict';

import { API_CALL } from '../constants/api';
import * as APIUtils from '../utils/api';

function getFormData(obj) {
	let formData = new FormData();
	
	Object
		.keys(obj)
		.forEach((key) => formData.append(key, obj[key]));
	
	return formData;
}

function checkStatus(response) {  
  if (response.status >= 200 && response.status < 300) {  
    return Promise.resolve(response);
  } else {  
    return Promise.reject(new Error(response.statusText));
  }  
}

function toJson(response) {  
  return response.json();
}

function callApi(url, data, method = 'POST') {
	
	let params = {
		method: method.toUpperCase(),
		credentials: 'same-origin'
	};
	
	if ( data ) {
		params.body = getFormData(data);
	}
	
	return fetch(url, params)
		.then(checkStatus)  
  	.then(toJson)  
  	.then(function(json) {
			if( json.status === 'error' ) {
				return Promise.reject(json.data);
			}
			return Object.assign({}, json);
		})
		.catch(function(error) {
			return Promise.reject(error);
		});
}

export default store => next => action => {
	
	if (typeof action[ API_CALL ] === 'undefined') {
    return next(action);
  }
	
	let asyncCall;
	
	const validHTTPMethods = [ 'GET', 'POST', 'PUT', 'DELETE', 'HEAD' ];
	
	const {
		endpoint = ajaxurl,
		method = 'POST',
		...data
	} = action[ API_CALL ];
	
  const actionType = data.action;
	
	if (typeof actionType !== 'string') {
		throw new Error('Specify a string action type.');
	}
	if (typeof endpoint !== 'string' && typeof endpoint !== 'function') {
		throw new Error('Specify a string endpoint URL.');
	}
	if (typeof method !== 'undefined' && validHTTPMethods.indexOf(method.toUpperCase()) === -1) {
		throw new Error('Invalid HTTP Method passed as method option.');
	}
	if (typeof endpoint === 'function') {
		asyncCall = endpoint(store.getState());
	} else {
		asyncCall = callApi(endpoint, data, method);
	}

	function actionWith(data) {
		const finalAction = Object.assign({}, action, data);
		delete finalAction[API_CALL];
		return finalAction;
	}

	next(actionWith({
		type: APIUtils.toRequestActionType(actionType)
	}));
	
	return asyncCall.then(
		response => next(actionWith({
			type: APIUtils.toSuccessActionType(actionType),
			data: response.data
		})),
		error => next(actionWith({
			type: APIUtils.toErrorActionType(actionType),
			error
		}))
	);
};
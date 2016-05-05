'use strict';
import {
	createAction,
}
from 'redux-actions';

import {
	abstractList
}
from './abstract-list-action';

import {
	LIST_SHARED,
	CREATE_SHARED,
	EXTEND_SHARED,
	DESTROY_SHARED,
}
from '../constants/shared';

import {
	API_CALL,
} from '../constants/api';

// Listing of shared documents
export const listShared = abstractList(LIST_SHARED, 'shared');

// Creating new shared document
function createSharedAPI(post, expires, metric) {
	return {
	[API_CALL]: {
			action: CREATE_SHARED,
			post,
			expires,
			metric,
		}
	};
}

export const createShared = function (post, expires, metric) {
	var args = Array.from(arguments);
	return (dispatch) => {
		return dispatch(createSharedAPI(post, expires, metric));
	};
};

// Extending existing shared document
function extendSharedAPI(shared, expires, metric) {
	return {
	[API_CALL]: {
			action: EXTEND_SHARED,
			shared, 
			expires, 
			metric,
		}
	};
}

export const extendShared = function (shared, expires, metric) {
	return (dispatch) => {
		return dispatch(extendSharedAPI(shared, expires, metric));
	};
};

// Destroyin shared document
function destroySharedAPI(shared) {
	return {
	[API_CALL]: {
			action: DESTROY_SHARED,
			shared,
		}
	};
}

export const destroyShared = function (shared) {
	return (dispatch) => {
		return dispatch(destroySharedAPI(shared));
	};
};
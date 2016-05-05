'use strict';
import { toErrorActionType, toRequestActionType, toSuccessActionType } from '../utils/api';
import { LIST_SHARED, CREATE_SHARED, EXTEND_SHARED, DESTROY_SHARED } from '../constants/shared';
import { filter } from 'lodash';

const LIST_SHARED_REQUEST = toRequestActionType(LIST_SHARED);
const LIST_SHARED_SUCCESS = toSuccessActionType(LIST_SHARED);
const LIST_SHARED_ERROR = toErrorActionType(LIST_SHARED);

const CREATE_SHARED_REQUEST = toRequestActionType(CREATE_SHARED);
const CREATE_SHARED_SUCCESS = toSuccessActionType(CREATE_SHARED);
const CREATE_SHARED_ERROR = toErrorActionType(CREATE_SHARED);

const EXTEND_SHARED_REQUEST = toRequestActionType(EXTEND_SHARED);
const EXTEND_SHARED_SUCCESS = toSuccessActionType(EXTEND_SHARED);
const EXTEND_SHARED_ERROR = toErrorActionType(EXTEND_SHARED);

const DESTROY_SHARED_REQUEST = toRequestActionType(DESTROY_SHARED);
const DESTROY_SHARED_SUCCESS = toSuccessActionType(DESTROY_SHARED);
const DESTROY_SHARED_ERROR = toErrorActionType(DESTROY_SHARED);


const initialState = {
  shared: [],
  isFetching: false
};


export default function (state = initialState, action) {
	var reduceFns = { };
		
	reduceFns[LIST_SHARED_REQUEST] = (state, action) => ({
		...state,
		isFetching: true
	});

	reduceFns[LIST_SHARED_ERROR]= (state, action) => ({
		...state,
		isFetching: false,
	});
	
	reduceFns[LIST_SHARED_SUCCESS]= (state, action) => ({
		...state,
		isFetching: false,
		shared: [ ...state.shared, ...action.data ],
	});
		
	/* Create New Shared */
	reduceFns[CREATE_SHARED_REQUEST] = reduceFns[LIST_SHARED_REQUEST];
	
	reduceFns[CREATE_SHARED_ERROR] 	= reduceFns[LIST_SHARED_ERROR];
	
	reduceFns[CREATE_SHARED_SUCCESS] = (state, action) => ({
		...state,
		isFetching: false,
		shared: [ ...state.shared, action.data ],
	});
	
	/* Edit(Extend) Existing Shared posts*/
	reduceFns[EXTEND_SHARED_REQUEST] = reduceFns[LIST_SHARED_REQUEST];
	
	reduceFns[EXTEND_SHARED_ERROR] 	= reduceFns[LIST_SHARED_ERROR];
	
	reduceFns[EXTEND_SHARED_SUCCESS] = (state, action) => {
		let index = state.shared.findIndex(share => share.shared_key === action.data.shared_key)
		
		let shared = Array.from(state.shared);
		
		shared[index] = action.data;
		
		return {
			...state,
			isFetching: false,
			shared
		};
	};
	
	/* Destroy Existing Shared posts*/
	reduceFns[DESTROY_SHARED_REQUEST] = reduceFns[LIST_SHARED_REQUEST];
	
	reduceFns[DESTROY_SHARED_ERROR] 	= reduceFns[LIST_SHARED_ERROR];
	
	reduceFns[DESTROY_SHARED_SUCCESS] = (state, action) => {
		let shared = state.shared.filter( share => share.shared_key !== action.data )
		
		return {
			...state,
			isFetching: false,
			shared
		};
	};
	
	return ( reduceFns[action.type] && reduceFns[action.type](state, action) ) || state;
}
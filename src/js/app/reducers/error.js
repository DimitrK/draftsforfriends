'use strict';

import {
	handleActions
}
from 'redux-actions';

import {
	LIST_DRAFTS
}
from '../constants/draft';
	
import {
	LIST_SHARED, EXTEND_SHARED, DESTROY_SHARED, CREATE_SHARED
}
from '../constants/shared';

import {
	LIST_SETTINGS
}
from '../constants/setting';

import {
	toRequestActionType, toSuccessActionType, toErrorActionType
}
from '../utils/api';

import {
	RESET_ERROR
}
from '../constants/error';
const LIST_SETTINGS_ERROR = toErrorActionType(LIST_SETTINGS);
const LIST_SHARED_ERROR = toErrorActionType(LIST_SHARED);
const LIST_DRAFTS_ERROR = toErrorActionType(LIST_DRAFTS);
const EXTEND_SHARED_ERROR = toErrorActionType(EXTEND_SHARED);
const DESTROY_SHARED_ERROR = toErrorActionType(DESTROY_SHARED);
const CREATE_SHARED_ERROR = toErrorActionType(CREATE_SHARED);

let initialState = {
	message: undefined
};


export default (state = initialState, action) => {
	const reduceFns = {
		[RESET_ERROR]: (state, action) => ({
			...state,
			message: undefined
		}),

		[LIST_SETTINGS_ERROR]: (state, action) => ({
			...state,
			message: action.error
		}),
	};

	reduceFns[LIST_DRAFTS_ERROR] = reduceFns[LIST_SETTINGS_ERROR]
	reduceFns[LIST_SHARED_ERROR] = reduceFns[LIST_SETTINGS_ERROR]
	reduceFns[DESTROY_SHARED_ERROR] = reduceFns[LIST_SETTINGS_ERROR]
	reduceFns[CREATE_SHARED_ERROR] = reduceFns[LIST_SETTINGS_ERROR]
	reduceFns[EXTEND_SHARED_ERROR] = reduceFns[LIST_SETTINGS_ERROR]
		
	
	return ( reduceFns[action.type] && reduceFns[action.type](state, action) ) || state;
};
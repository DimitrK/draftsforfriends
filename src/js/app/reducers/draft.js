'use strict';
import {
	LIST_DRAFTS
}
from '../constants/draft';

import {
	toRequestActionType, toSuccessActionType, toErrorActionType
}
from '../utils/api';

const LIST_DRAFTS_REQUEST = toRequestActionType(LIST_DRAFTS);
const LIST_DRAFTS_SUCCESS = toSuccessActionType(LIST_DRAFTS);
const LIST_DRAFTS_ERROR = toErrorActionType(LIST_DRAFTS);

const initialState = {
	draftsByType: {},
	isFetching: false
};


export default function (state = initialState, action) {
	var reduceFns = {
		[LIST_DRAFTS_REQUEST]: (state, action) => ({
			...state,
			isFetching: true
		}),

		[LIST_DRAFTS_SUCCESS]: (state, action) => ({
			...state,
			isFetching: false,
			draftsByType: {
				...state.draftsByType, 
				...action.data
			},
		}),

		[LIST_DRAFTS_ERROR]: (state, action) => ({
			...state,
			isFetching: false
		}),
		
	};

	return ( reduceFns[action.type] && reduceFns[action.type](state, action) ) || state;
}
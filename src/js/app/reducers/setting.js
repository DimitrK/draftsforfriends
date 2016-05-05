'use strict';
import { toErrorActionType, toRequestActionType, toSuccessActionType } from '../utils/api';
import { LIST_SETTINGS } from '../constants/setting';

const LIST_SETTINGS_REQUEST = toRequestActionType(LIST_SETTINGS);
const LIST_SETTINGS_SUCCESS = toSuccessActionType(LIST_SETTINGS);
const LIST_SETTINGS_ERROR		= toErrorActionType(LIST_SETTINGS);

const initialState = {
	translations: {},
	isFetching: true,
};

export default ( state = initialState, action ) => {
	
	const reduceFns = {
		[LIST_SETTINGS_REQUEST] : ( state, action ) => ({
			...state,
			isFetching: true,
		}),
		
		[LIST_SETTINGS_SUCCESS] : ( state, action ) => ({
			...state,
			isFetching: false,
			translations: {
				...state.translations, 
				...action.data.translations
			},
		}),
		
		[LIST_SETTINGS_ERROR] 	: ( state, action ) => ({
			...state,
			isFetching: true,
		}),
	};
	
	return ( reduceFns[action.type] && reduceFns[action.type](state, action) ) || state;
};
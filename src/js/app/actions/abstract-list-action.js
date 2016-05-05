'use strict';
import {
	API_CALL,
} from '../constants/api';

import {
	isEmpty,
} from 'lodash';
	
function fetchActionEntity(action) {
	return {
    	[API_CALL]: {
			action
		}
	};
}

export const abstractList = (action, stateProperty) => {
	
	return () => (dispatch, getState) => {
		console.log('dispatching '+action);
		const stateExistingValue = getState()[stateProperty];

		if ( isEmpty( stateExistingValue ) || Object.keys(stateExistingValue).every((key) => (isEmpty(stateExistingValue[key]))) ) {
			return dispatch(fetchActionEntity(action));
		} else {
			console.log(`State for ${action} exists with value ${stateExistingValue}. Exiting.`);
			return null;
		}

	};
};
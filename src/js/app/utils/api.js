'use strict';

export const toErrorActionType = function(actionType) {
	return `${actionType}_ERROR`;
};
export const toSuccessActionType = function(actionType) {
	return `${actionType}_SUCCESS`;
};
export const toRequestActionType = function(actionType) {
	return `${actionType}_REQUEST`;
};
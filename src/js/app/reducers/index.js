'use strict';
import { combineReducers } from 'redux';
import draft from './draft';
import shared from './shared';
import setting from './setting';
import error from './error';


export default combineReducers({
  draft,
	shared,
	setting,
	error
});
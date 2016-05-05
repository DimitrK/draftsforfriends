'use strict';
import {
	LIST_SETTINGS
}
from '../constants/setting';

import { abstractList } from './abstract-list-action';
	
export const listSettings = abstractList(LIST_SETTINGS, 'setting');
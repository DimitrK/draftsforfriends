'use strict';
import {
	abstractList,
} from './abstract-list-action';

import {
	LIST_DRAFTS,
}
from '../constants/draft';

export const listDrafts = abstractList(LIST_DRAFTS, 'draft');
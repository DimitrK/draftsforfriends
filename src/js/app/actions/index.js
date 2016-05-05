'use strict';

import * as draftActions from './draft-actions';
import * as sharedActions from './shared-actions';
import * as settingActions from './setting-actions';
import * as errorActions from './error-actions';

export default {
	...draftActions,
	...sharedActions,
	...settingActions,
	...errorActions,
};
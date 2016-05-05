import * as drafts from './draft';
import * as settings from './setting';
import * as shared from './shared';
import * as api from './api';
import * as error from './error';


export default {
	...api,
	...drafts,
	...shared,
	...settings,
	...error,
};
import {
	Provider
}
from 'react-redux';
import {
	render
}
from 'react-dom';
import React from 'react';

import App from './containers/App';
import store from './store';

if (document.getElementById('dff-client-root')) {
	render( 
		<Provider store = { store } >
			<App />
		</Provider>,
		document.getElementById('dff-client-root')
	);
}
'use strict';

import React, { Component, PropTypes } from 'react';
import { connect } from 'react-redux';
import style from './style.css';

const Loader = ({ isVisible, message, children, ...restProps }) => {
	let messageElement = null;
								 
  if ( message ) {
		messageElement = (<h2>{ message }</h2>);
  }
								 
	if ( isVisible ) {
		
		return (
			<div 
				{...restProps}
				className={ style.overlay }
			>
				{ messageElement }
				{ children }
			</div>
		);
	} else {
		
		return false;
	}
}

Loader.propTypes = {
	isVisible		: PropTypes.bool.isRequired,
	message			: PropTypes.string,
};

export default Loader;

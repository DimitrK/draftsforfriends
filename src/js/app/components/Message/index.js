'use strict';
import React, { PropTypes } from 'react';

const Message = ({ message, children}) => {
	if( message ) {
		return (
			<div id="message" className="updated fade"> 
				{ message }
				{ children }
			</div>
		);
	}
	return false;
}

Message.propTypes = {
  message: PropTypes.string,
  children: PropTypes.node
}

export default Message;
'use strict';
import React, { PropTypes } from 'react';

const Link = ({url, onClick, children, ...restLinkProps }) => {
	
	const encodedUrl = encodeURI(url)
	
	const handleClick = (e) => {
		if ( onClick ) {
			e.preventDefault();
			onClick();
		}
	};
	
  return (
    <a 
		{ ...restLinkProps }
		href={ encodedUrl } 
		target="_blank"
		onClick={ handleClick } 
		>
			{ children ? children : url }
    </a>
  );
}

Link.propTypes = {
  url: PropTypes.bool.isRequired,
  onClick: PropTypes.func
}

export default Link
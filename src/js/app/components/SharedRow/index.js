'use strict';

import React, { Component, PropTypes } from 'react';
import { connect } from 'react-redux';
import Link from '../Link';

const SharedRow = ({ shared, children }) => {
	const { post_id, post_title, shared_key, shared_expires } = shared;
	
	const previewUrl = window.location.origin + '/?p=' + post_id + '&draftsforfriends=' + shared_key;
	
	return(
		<tr>
			<td>{ post_id }</td>
			<td>{ post_title }</td>
			<td>
				<Link url={ previewUrl }/>
			</td>
			<td>{ shared_expires }</td>
			{ children }
		</tr>
	);
	
};

SharedRow.propTypes = {
	shared: PropTypes.shape({
		shared_key		: PropTypes.string.isRequired,
		shared_expires: PropTypes.string.isRequired,
		post_id				: PropTypes.string.isRequired,
		post_title		: PropTypes.string.isRequired,
	}).required
}

export default SharedRow;
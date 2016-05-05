'use strict';
import React, { Component, PropTypes } from 'react';
import {connect} from 'react-redux';
import SharedForm from '../../components/SharedForm';
import { extendShared } from '../../actions/shared-actions';
import style from './style.css';

const ExtendSharedForm = ({sharedKey, translations, extendShared, children, ...restFormProps }) => {
		
	return (
		<SharedForm 
			{...restFormProps}
			className={ style['draftsforfriends-extend'] }
			joinText = { translations.text_by }
			submitText={ translations.extend_button }
			submitAction={ extendShared }
			translations={ translations }
			sourceId = { sharedKey }
		> 
		{ children }
		</SharedForm>
	);
}

ExtendSharedForm.propTypes = {
	sharedKey		: PropTypes.string.isRequired,
	children 		: PropTypes.node
}

const mapStateToProps = (state) => {
	const { translations } = state.setting;
	return {
		translations
	};
};

const mapDispatchToProps = (dispatch) => {
	return {
		extendShared : (shared, expires, metric) => dispatch(extendShared(shared, expires, metric))
	}
};

export default connect(mapStateToProps, mapDispatchToProps)(ExtendSharedForm);

import React, { PropTypes } from 'react';
import { connect } from 'react-redux';
import { destroyShared } from '../../actions/shared-actions';
import Link from '../../components/Link';

const DestroySharedLink = ({ sharedKey, translations, destroyShared }) => {
	
	return (
		<Link
			url = '#'
			onClick = { () => destroyShared(sharedKey) }
		>
			{ translations.delete_button }
		</Link>
	);
}

DestroySharedLink.propTypes = {
	sharedKey: PropTypes.string.required
}

const mapStateToProps = (state) => {
	const { translations } = state.setting
	return {
		translations
	};
}

const mapDispatchToProps = (dispatch) => {
	
	return {
		destroyShared: (sharedKey) => dispatch(destroyShared(sharedKey))
	};
}

export default connect(mapStateToProps, mapDispatchToProps)(DestroySharedLink);
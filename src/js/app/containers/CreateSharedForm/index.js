'use strict';
import React, { Component, PropTypes } from 'react';
import {connect} from 'react-redux';
import SharedForm from '../../components/SharedForm';
import GroupedSelect from '../../components/GroupedSelect';
import { createShared } from '../../actions/shared-actions';
import { listDrafts }	from '../../actions/draft-actions';


class CreateSharedForm extends Component {
	
	constructor(props) {
		super(props);
		this.state = { draftId: -1 };
	}
	
	componentWillMount() {
		this.props.listDrafts();
	}
	
	onSelectedDraftChanged(event) {
		this.setState({ draftId: event.target.value });
	}
	
	render() {
		const {
			postId, 
			translations, 
			createShared, 
			children,
			draftsByType,
			isFetching,
			...restFormProps,
		} = this.props;
		
		let draftsSelectSchema = {
			selectValue: 'ID',
			selectText : 'post_title'
		};
		
		return (
			<SharedForm 
				{...restFormProps}
				submitText={ translations.shareit_button }
				joinText={ translations.text_for }
				submitAction={ createShared }
				translations={ translations }
				sourceId = { this.state.draftId }
				appendChildren = { false }
			>
			<p>
				<GroupedSelect
					onChange={ ::this.onSelectedDraftChanged }
					groupedObject={ draftsByType }
					selectSchema={ draftsSelectSchema }
				/>
			</p>
			</SharedForm>
		);
	}
}

CreateSharedForm.propTypes = {
	postId			: PropTypes.string.isRequired,
	children 		: PropTypes.node
}

const mapStateToProps = (state) => {
	const { translations } = state.setting;
	return {
		translations,
		...state.draft
	};
};

const mapDispatchToProps = (dispatch) => {
	return {
		createShared : (id, expires, metric) => { 
			let args = Array.from(arguments);
			dispatch(createShared(id, expires, metric)) 
		},
		listDrafts	 : () => { 
			dispatch(listDrafts())
		}
	}
};

export default connect(mapStateToProps, mapDispatchToProps)(CreateSharedForm);
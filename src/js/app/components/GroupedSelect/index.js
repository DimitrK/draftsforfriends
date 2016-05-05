'use strict';

import React, {
	Component, PropTypes
}
from 'react';

import { connect } from 'react-redux';

class GroupedSelect extends Component {

	getOptionsPerGroup(groupName, groupOptions) {
		
		const { selectValue, selectText } = this.props.selectSchema;
		
		let options = [];
		options.push( (<option value='' disabled='disabled' ></option>) );
		options.push( (<option value='' disabled='disabled' >{ groupName }</option>) );
			
		options = options.concat( 
			groupOptions.map(option => (
				<option 
					value={ option[selectValue] }  >
					{ option[selectText] }
				</option>
			))
		);
		
		return options
	}
	
	render() {
		
		const {
			translations,
			groupedObject,
			...restSelectProps
		} = this.props;

		return (
			<select
				{...restSelectProps}
			>				
				<option value='' disabled='disabled'>{ translations.choose_draft }</option>
		
				{ Object.keys(groupedObject).map( groupTitle => (
						this.getOptionsPerGroup(groupTitle, groupedObject[groupTitle]) 
				) ) }
		
			</select>
		);
	}
};

GroupedSelect.propTypes = {
	groupedObject: PropTypes.shape({
		someGroupName: PropTypes.array,
		anotherGroupName: PropTypes.array
	}),
	selectSchema: PropTypes.shape({
		selectValue: PropTypes.string.isRequired,
		selectText : PropTypes.string.isRequired
	}).isRequired
};

const mapStateToProps = (state) => {
	const { translations } = state.setting;
	return {
		translations
	};
}

export default connect(mapStateToProps)(GroupedSelect);

//<p>
//			<select id="draftsforfriends-postid" 	name="post_id">
//			<option value=""><?php _e('Choose a draft', 'draftsforfriends'); ?></option>
//<?php
//		foreach($ds as $dt):
//			if ($dt[1]):
//?>
//			<option value="" disabled="disabled"></option>
//			<option value="" disabled="disabled"><?php echo $dt[0]; ?></option>
//<?php
//				foreach($dt[2] as $d):
//					if (empty($d->post_title)) continue;
//?>
//			<option value="<?php echo $d->ID?>"><?php echo esc_html($d->post_title); ?></option>
//<?php
//				endforeach;
//			endif;
//		endforeach;
//?>
//			</select>
//		</p>


//	{
//		actions_column: "Actions"
//		cancel_button: "Cancel"
//		curently_shared: "Currently shared drafts"
//		delete_button: "Delete"
//		drafts_title: "Drafts for Friends"
//		expires_column: "Expires"
//		extend_button: "Extend"
//		for: "for"
//		id_column: "ID"
//		link_column: "Link"
//		no_drafts: "No shared drafts!"
//		shareit_button: "Share it"
//		title_column: "Title",
//  	loading: 'loading'
//'choose_draft' => __('Choose a draft', 'draftsforfriends'),
	//	}
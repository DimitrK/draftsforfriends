'use strict';
import React, { Component, PropTypes } from 'react';

class BaseSharedForm extends Component {
	
	handleClick(e) {
		e.preventDefault();
		
		const {submitAction, sourceId} = this.props
		
		submitAction(sourceId, this.refs.expValueInput.value, this.refs.expMetricSelect.value);
	}
	
	render() {
		
		const { 
			translations, 
			sourceId, 
			submitAction,
			submitText,
			joinText,
			children,
			appendChildren,
			...restFormProps,
		} = this.props;
		
		return (
			<form { ...restFormProps }>
				{ appendChildren ? null : children }
				<input 
					type="submit" 
					className="button" 
					name="draftsforfriends_extend_submit" 
					value={ submitText }
					onClick={ ::this.handleClick }
				/>
				{ " " + joinText + " " }
				<input 
					name="expires" 
					type="number" 
					defaultValue="2" 
					size="4"
					ref="expValueInput"
				/>
				<select 
					name="measure"
					ref="expMetricSelect"
				>
					<option value="s">{ translations.seconds }</option>
					<option value="m">{ translations.minutes }</option>
					<option value="h" selected="selected">{ translations.hours }</option>
					<option value="d">{ translations.days }</option>
				</select>
				{ appendChildren ? children : null }
			</form>
		);
	}
}

BaseSharedForm.propTypes = {
	translations	: PropTypes.array.isRequired,
	sourceId			: PropTypes.string.isRequired,
	submitText		: PropTypes.string.isRequired,
	joinText			: PropTypes.string.isRequired,
	submitAction	: PropTypes.func.isRequired,
	children 			: PropTypes.node,
	appendChildren: PropTypes.bool,
}

BaseSharedForm.defaultProps = {
	appendChildren: true,
}

export default BaseSharedForm;

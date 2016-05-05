'use strict';

import { connect } from 'react-redux';
import Message from '../../components/Message';

function mapStateToProps(state) {
  return {
		message: state.error.message
  };
}

export default connect(
  mapStateToProps
)(Message);
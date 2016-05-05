'use strict';

import { RESET_ERROR } from '../constants/error';

export const resetError = function() {
  return {
    type: RESET_ERROR
  };
};
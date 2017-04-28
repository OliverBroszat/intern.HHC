#! /usr/bin/python
# -*- coding: iso-8859-1 -*-

import json

import BaseHandler


class ApiHandler(BaseHandler.BaseHandler):
    
    LOG_INVALID_API_TOKEN = "Received invalid api token {token}"

    def api_token_is_invalid(self):
        api_token = self.get_argument("api_token")
        return self._is_invalid_api_token(api_token)

    def write_invalid_api_token_response(self):
        data = {
            "error": "Invalid API key"
        }
        self.write(json.dumps(data))

    def write_error_response(self, e):
        data = {
            "error": repr(e)
        }
        self.write(json.dumps(data))

    def _is_invalid_api_token(self, api_token):
        query = "SELECT * FROM `api_tokens` WHERE api_token=?;"
        data = self.context.database.get_single_value_by_query(query, (api_token,))
        if not data:
            self.context.logger.debug(ApiHandler.LOG_INVALID_API_TOKEN.format(token=api_token))
            return True
        return False
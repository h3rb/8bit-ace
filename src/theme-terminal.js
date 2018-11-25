/* ***** BEGIN LICENSE BLOCK *****
 * Distributed under the BSD license:
 *
 * Copyright (c) 2010, Ajax.org B.V.
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of Ajax.org B.V. nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL AJAX.ORG B.V. BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * ***** END LICENSE BLOCK ***** */

define('ace/theme/terminal', ['require', 'exports', 'module' , 'ace/lib/dom'], function(require, exports, module) {

exports.isDark = true;
exports.cssClass = "ace-terminal";
exports.cssText = ".ace-terminal .ace_gutter {\
background: #010;\
color: #00AA00\
}\
.ace-terminal .ace_print-margin {\
width: 1px;\
background: #1a1a00\
}\
.ace-terminal .ace_scroller {\
background-color: #000000\
}\
.ace-terminal .ace_text-layer {\
color: #DEDEDE\
}\
.ace-terminal .ace_cursor {\
border-left: 2px solid orangered\
}\
.ace-terminal .ace_overwrite-cursors .ace_cursor {\
border-left: 0px;\
border-bottom: 1px solid springgreen\
}\
.ace-terminal .ace_marker-layer .ace_selection {\
background: #00F\
}\
.ace-terminal.ace_multiselect .ace_selection.ace_start {\
box-shadow: 0 0 3px 0px #000000;\
border-radius: 2px\
}\
.ace-terminal .ace_marker-layer .ace_step {\
background: rgb(102, 82, 0)\
}\
.ace-terminal .ace_marker-layer .ace_bracket {\
margin: -1px 0 0 -1px;\
background: #900\
}\
.ace-terminal .ace_marker-layer .ace_active-line {\
background: #2A0002\
}\
.ace-terminal .ace_gutter-active-line {\
background-color: #2A2A00\
}\
.ace-terminal .ace_marker-layer .ace_selected-word {\
background-color: #006;\
border: 1px solid #4242AA\
}\
.ace-terminal .ace_invisible {\
color: #343434\
}\
.ace-terminal .ace_keyword,\
.ace-terminal .ace_meta,\
.ace-terminal .ace_storage,\
.ace-terminal .ace_storage.ace_type,\
.ace-terminal .ace_support.ace_type {\
color: #C397D8\
}\
.ace-terminal .ace_keyword.ace_operator {\
color: #39F\
}\
.ace-terminal .ace_constant.ace_character,\
.ace-terminal .ace_constant.ace_language,\
.ace-terminal .ace_constant.ace_numeric,\
.ace-terminal .ace_keyword.ace_other.ace_unit,\
.ace-terminal .ace_support.ace_constant {\
color: brown\
}\
.ace-terminal .ace_constant.ace_other {\
color: #E0F\
}\
.ace-terminal .ace_invalid {\
color: #CED2CF;\
background-color: #DED\
}\
.ace-terminal .ace_invalid.ace_deprecated {\
color: #CED2CF;\
background-color: #B798BF\
}\
.ace-terminal .ace_fold {\
background-color: #7AA6DA;\
border-color: #DEDEDE\
}\
.ace-terminal .ace_entity.ace_name.ace_function,\
.ace-terminal .ace_support.ace_function,\
.ace-terminal .ace_variable {\
color: #3F3\
}\
.ace-terminal .ace_support.ace_class,\
.ace-terminal .ace_support.ace_type {\
color: #E7C547\
}\
.ace-terminal .ace_identifier {\
 color: #090\
}\
.ace-terminal .ace_paren {\
 color: gold\
}\
.ace-terminal .ace_markup.ace_heading,\
.ace-terminal .ace_string {\
color: #BC0\
}\
.ace-terminal .ace_entity.ace_name.ace_tag,\
.ace-terminal .ace_entity.ace_other.ace_attribute-name,\
.ace-terminal .ace_meta.ace_tag,\
.ace-terminal .ace_string.ace_regexp,\
.ace-terminal .ace_variable {\
color: deeppink\
}\
.ace-terminal .ace_comment {\
color: #DD9896\
}\
.ace-terminal .ace_markup.ace_underline {\
text-decoration: underline\
}\
.ace-terminal .ace_indent-guide {\
background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAACCAYAAACZgbYnAAAAEklEQVQImWNgYGD4z7Bq1ar/AAz9A/2naJQKAAAAAElFTkSuQmCC) right repeat-y\
}";

var dom = require("../lib/dom");
dom.importCssString(exports.cssText, exports.cssClass);
});

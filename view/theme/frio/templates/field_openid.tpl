{{*
  * Copyright (C) 2010-2024, the Friendica project
  * SPDX-FileCopyrightText: 2010-2024 the Friendica project
  *
  * SPDX-License-Identifier: AGPL-3.0-or-later
  *}}
<div id="id_{{$field.0}}_wrapper" class="form-group field input openid">
	<label for="id_{{$field.0}}" id="label_{{$field.0}}">{{$field.1}}</label>
	<input class="form-control" name="{{$field.0}}" id="id_{{$field.0}}" type="text" value="{{$field.2}}" {{if $field.4}}readonly{{/if}} aria-describedby="{{$field.0}}_tip">
	{{if $field.3}}
	<span class="help-block" id="{{$field.0}}_tip" role="tooltip">{{$field.3 nofilter}}</span>
	{{/if}}
	<div class="clear"></div>
</div>

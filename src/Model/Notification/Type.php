<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Model\Notification;

/**
 * Enum for different types of the Notify
 */
class Type
{
	/** @var int Notification about a introduction */
	const INTRO = 1;
	/** @var int Notification about a confirmed introduction */
	const CONFIRM = 2;
	/** @var int Notification about a post on your wall */
	const WALL = 4;
	/** @var int Notification about a followup comment */
	const COMMENT = 8;
	/** @var int Notification about a private message */
	const MAIL = 16;
	/** @var int Notification about a friend suggestion */
	const SUGGEST = 32;
	/** @var int Notification about being tagged in a post */
	const TAG_SELF = 128;
	/** @var int Notification about getting poked/prodded/etc. (Obsolete) */
	const POKE = 512;
	/** @var int Notification about either a contact had posted something directly or the contact is a mentioned group */
	const SHARE = 1024;

	/** @var int Global System notifications */
	const SYSTEM = 32768;
}

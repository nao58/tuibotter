<?php

/**
 * Event which is called when friends timeline has been updated.
 */
interface TuiBotter_Event_UpdatedFriendsTL
{
	public function eventUpdatedFriendsTL(Tuitter_Tweet $tweet, Tuitter $tuitter);
}

/**
 * Event which is called when the user has been mentioned.
 */
interface Tuibotter_Event_Mentioned
{
	public function eventMentioned(Tuitter_Tweet $tweet, Tuitter $tuitter);
}

/**
 * Event which is called when the user has been got reply.
 */
interface Tuibotter_Event_Replied
{
	public function eventReplied(Tuitter_Tweet $tweet, Tuitter $tuitter);
}

/**
 * Event which is called when the user has been followed.
 */
interface TuiBotter_Event_Followed
{
	public function eventFollowed(Tuitter_User $user, Tuitter $tuitter);
}

/**
 * Event which is called when the user has been got direct message.
 */
interface TuiBotter_Event_GotDM
{
	public function eventGotDM(Tuitter_DM $dm, Tuitter $tuitter);
}

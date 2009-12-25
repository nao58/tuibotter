<?php

/**
 * Event which is called when the user has been followed.
 */
interface TuiBotter_Event_BeFollowed
{
	public function eventBeFollowed(Tuitter_User $user, Tuitter $tuitter);
}

/**
 * Event which is called when friends timeline has been updated.
 */
interface TuiBotter_Event_UpdatedHomeTL
{
	public function eventUpdatedHomeTL(Tuitter_Tweet $tweet, Tuitter $tuitter);
}

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
interface TuiBotter_Event_BeMentioned
{
	public function eventBeMentioned(Tuitter_Tweet $tweet, Tuitter $tuitter);
}

/**
 * Event which is called when the user has been got reply.
 */
interface TuiBotter_Event_BeReplied
{
	public function eventBeReplied(Tuitter_Tweet $tweet, Tuitter $tuitter);
}

interface TuiBotter_Event_BeRetweeted
{
	public function eventBeRetweeted(Tuitter_Tweet $tweet, Tuitter $tuitter);
}

interface TuiBotter_Event_Retweeted
{
	public function eventRetweeted(Tuitter_Tweet $tweet, Tuitter $tuitter);
}

interface TuiBotter_Event_RetweetedToMe
{
	public function eventRetweetedToMe(Tuitter_Tweet $tweet, Tuitter $tuitter);
}

interface TuiBotter_Event_FavoriteMarked
{
	public function eventFavoriteMarked(Tuitter_Tweet $tweet, Tuitter $tuitter);
}

/**
 * Event which is called when the user has been got direct message.
 */
interface TuiBotter_Event_GotDM
{
	public function eventGotDM(Tuitter_DM $dm, Tuitter $tuitter);
}

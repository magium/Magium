<?php

namespace Magium\Util\Notification;

interface UserStoryNotificationInterface
{

    public function notify($userStoryIdentifier, $notice);

}
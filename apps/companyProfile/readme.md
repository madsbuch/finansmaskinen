Create new subscription:

If an app wants to hook into to subscription system, following callback id to be defined:

on_getCompanyProfileSubscription(){

}

It should return a \model\finance\company\Subscription object, that is empty.

that is, no details on whether the app is subscriped is to be filled in
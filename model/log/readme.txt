This folder contains log models or "templates"

You can provide the core\logHandler::log($obj) with one of those objects.
The objects are prepopulated with filename data and stuff, so the only thing the woory about is populating
the documentet fields.

don't use other fields than the documented ones. create custom logs (see logHandler documentation), extent the model
og create a new model
/**
 * Module: @josefglatz/development-only/context-menu-actions
 *
 * JavaScript to handle the click action of the "Hello World" context menu item
 */
import AjaxRequest from "@typo3/core/ajax/ajax-request.js";

class ContextMenuActions {

  setPagesOnFirstLevelToRootPages(table, uid) {
    if (table === 'pages') {
      if (uid === 0) {
        // execute get request on the backend controller
        new AjaxRequest(TYPO3.settings.ajaxUrls.development_only_backend_ajaxbackend_rootpages)
          .get()
          .then(async function (response) {
            // save AjaxResponse and make sure that content type is json (even if it is already in the controller set)
            const resolved = await response.resolve('application/json');
            // log answer in console
            console.log(resolved);
            // inform backend user about processed pages
            if (resolved.processedPages > 0) {
              top.TYPO3.Notification.success(
                'Done! ' + resolved.processedPages + ' pages processed.',
                'Those ' + resolved.processedPages + ' pages on rootlevel are root pages and hot hidden anymore.',
                10
              );
            } else {
              top.TYPO3.Notification.info(
                'No relevant root pages found ',
                'There no pages found on root level without empty siteroot property.',
                10
              );
            }

            // inform backend user if any errors where thrown in the TYPO3 DataHandler
            if (resolved.dataHandlerErrors >= 1) {
              top.TYPO3.Notification.error(
                resolved.dataHandlerErrors + ' TYPO3 DataHander errors occured',
                'Please check the TYPO3 log for potential errors',
                30
              );
            }

            // refresh TYPO3 page tree with event (this is not public TYPO3 API, however, thank you, Andreas Kienast)
            top.document.dispatchEvent(new CustomEvent('typo3:pagetree:refresh'));

          }, function (error) {
            top.TYPO3.Notification.error('Error occured!', 'An error occured while trying to make all root pages site roots.', 30);
          });

      } else {
      }


      //If needed, you can access other 'data' attributes here from $(this).data('someKey')
      //see item provider getAdditionalAttributes method to see how to pass custom data attributes
    }
  };
}

export default new ContextMenuActions();

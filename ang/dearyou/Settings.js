(function(angular, $, _) {

  angular.module('dearyou').config(function($routeProvider) {
      $routeProvider.when('/dearyou', {
        controller: 'DearyouSettings',
        templateUrl: '~/dearyou/Settings.html',

        // If you need to look up data when opening the page, list it out
        // under "resolve".
        resolve: {
          dearyouSettings: function(crmApi) {
            return crmApi('Setting', 'getvalue', { name: 'dearyou' })
            .then(r => JSON.parse(r.result || {}), e => alert('Error fetching settings.'));
          }
        }
      });
    }
  );

  // The controller uses *injection*. This default injects a few things:
  //   $scope -- This is the set of variables shared between JS and HTML.
  //   crmApi, crmStatus, crmUiHelp -- These are services provided by civicrm-core.
  //   myContact -- The current contact, defined above in config().
  angular.module('dearyou').controller('DearyouSettings', function($scope, crmApi, crmStatus, crmUiHelp, dearyouSettings) {

    // The ts() and hs() functions help load strings for this module.
    var ts = $scope.ts = CRM.ts('dearyou');
    var hs = $scope.hs = crmUiHelp({file: 'CRM/dearyou/Settings'}); // See: templates/CRM/dearyou/Settings.hlp

    // Check settings is minimally configured.
    if (!dearyouSettings.hasOwnProperty('tokens')) {
      dearyouSettings.tokens = {
        informal: {
          name: 'Informal',
          individual: {
            ifData: { pre: 'Hi ', prefs: 'nickname,first_name', post: ',' },
            ifNoData: { text: 'Hi,' }
          },
          household: {
            ifData: { pre: 'Hi ', prefs: 'display_name', post: ',' },
            ifNoData: { text: 'Hi,' }
          },
          organization: {
            ifData: { pre: 'Hi ', prefs: 'display_name', post: ',' },
            ifNoData: { text: 'Hi friends,' }
          }
        },
        formal: {
          name: 'Formal',
          individual: {
            ifData: { pre: 'Dear ', prefs: 'display_name', post: ',' },
            ifNoData: { text: 'Dear Supporter,' }
          },
          household: {
            ifData: { pre: 'Dear ', prefs: 'display_name', post: ',' },
            ifNoData: { text: 'Dear Supporters,' }
          },
          organization: {
            ifData: { pre: 'Dear ', prefs: 'display_name', post: ',' },
            ifNoData: { text: 'Dear Supporters,' }
          }
        },
      };
    }

    $scope.dearyouSettings = dearyouSettings;
    $scope.exampleNames = {
      individual: 'Wilma',
      household: 'Superstar Household',
      organization: 'Campaign Network X',
    };

    $scope.saveSettings = function saveSettings() {
      return crmStatus(
        // Status messages. For defaults, just use "{}"
        {start: ts('Saving...'), success: ts('Saved')},
        crmApi('Setting', 'create', { 'dearyou': JSON.stringify(dearyouSettings) })
      );
    }

  });

})(angular, CRM.$, CRM._);

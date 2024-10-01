import $ from 'jquery';
import session from 'session';

function getUserLocation() {
  return $.ajax({
    type: 'GET',
    url: 'https://ipinfo.io/json',
    timeout: 3000
  });
}

function generateStandardUserInfo() {
  const screen = window.screen ? window.screen : {};
  const history = window.history ? window.history : {};
  const location = window.location ? window.location : {};
  return {
    locale: {
      lang: navigator.language
    },
    current_session: {
      url: location.href,
      path: location.pathname,
      referrer: document.referrer
    },
    browser: {
      browser: navigator.userAgent,
      version: navigator.appVersion,
      os: navigator.platform
    },
    time: {
      tz_offset: -(new Date().getTimezoneOffset()) / 60
    },
    device: {
      screen: {
        width: screen.width,
        height: screen.height
      },
      viewport: {
        width: window.innerWidth,
        height: window.innerHeight
      }
    },
    extra: {
      previousSites: history.length,
      browserName: navigator.appName,
      browserEngine: navigator.product,
      browserOnline: navigator.onLine,
      dataCookiesEnabled: navigator.cookieEnabled,
      sizeDocW: document.width,
      sizeDocH: document.height,
      sizeAvailW: screen.availWidth,
      sizeAvailH: screen.availHeight,
      scrColorDepth: screen.colorDepth,
      scrPixelDepth: screen.pixelDepth
    }
  };
}

export default function getUserInfo() {
  const userInfoDfd = $.Deferred();
  const locationDfd = $.Deferred();

  session(window, document, navigator, userInfoDfd.resolve);
  getUserLocation().done((response) => locationDfd.resolve(response));
  setTimeout(() => {
    userInfoDfd.resolve({});
    locationDfd.resolve(null);
  }, 3000);

  // eslint-disable-next-line prefer-object-spread
  return $.when(userInfoDfd, locationDfd).then((userInfoData, location) => Object.assign(
    {},
    { locationUserInfo: location },
    generateStandardUserInfo(),
    userInfoData
  ));
}

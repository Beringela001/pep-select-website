# JavaScript directory

Version 0.4.0-beta.2 retains `header.js` and adds `homepage.js`. Both scripts are dependency-free and component-scoped. `homepage.js` loads only for the authorized private homepage preview and controls the FAQ accordion's expanded state and keyboard navigation.

The header script controls the mobile navigation's expanded state, closes it with Escape, restores focus to the toggle after an Escape close, closes after mobile navigation, and resets its state when resizing to desktop. Neither script calls customer, commerce, rewards, side-cart, analytics, tracking, or remote-request interfaces.

=== PS Access Gate ===
Version: 2.2.2
License: GPL-2.0+

Compliance-grade access verification gate for research-use-only sites.

== What's new in 2.2.2 ==
* Keep the page at its true top when the gate closes or a remembered consent
  cookie is detected. Focus still moves to the main content for accessibility,
  but no longer scrolls the global header out of view.

== What's new in 2.2.1 ==
* New "Backdrop opacity" setting (0-100%): controls the opacity of the dark
  area behind the card, so the page can show through. Applied to the container
  background only (rendered as rgba from the Background color) — the card
  itself stays fully opaque. Defaults to 100% (unchanged solid look).

== What's new in 2.2.0 ==
* New default "Simple" two-button layout: "Welcome" heading, one editable
  confirmation sentence, and side-by-side "I Agree" (solid navy) / "Under 21"
  (outline, exits to the configured Exit URL) buttons, a checked-by-default
  "Remember me" control, the scrollable legal/FDA box, and address/copyright.
* "I Agree" is immediately actionable — no disabled state, no required
  dropdown or checklist in Simple mode.
* Layout mode toggle in settings: switch to "Advanced" to restore the full
  researcher-type dropdown, checkbox list, and collapsible attestation. Those
  settings and code paths are fully preserved and used only in Advanced mode.
* Bundled brand logo: the gate now ships with the current PepSelect logo and
  shows it by default (settings toggle "Use the current bundled logo"), so the
  gate mark stays correct regardless of any older media-library logo.
* Engine unchanged: version-stamped cookie on I Agree (session vs remember
  days), consent recording via admin-ajax keepalive, form-version re-gating,
  automatic cache purge on save, logged-in-user skip, dialog/focus-trap/inert
  accessibility, and crawler-safe overlay (content stays in the DOM; the gate
  never redirects or cloaks). Simple mode records consent with an empty
  researcher type.
* Responsive: ~620px desktop card; fits 390x844 mobile without cutoff; legal
  box scrolls internally (~140px on mobile); buttons stack at <=360px with
  >=44px tap targets.

== What's new in 2.1.3 ==
* Reissue the verified accessibility build under a new version so production
  cannot retain an older package that also identified itself as 2.1.2.
* Preserve the existing fully blocking research-access behavior and settings.
* Include modal description wiring, native Exit-link semantics, initial focus,
  focus containment, background isolation, focus restoration, and responsive
  WordPress logo markup.

== What's new in 2.1.2 ==
* Correct the Terms and Conditions link to `/terms-conditions/`.
* Normalize the former `/terms-of-service/` URL in existing saved legal text
  without overwriting the rest of the configured wording.
* Add modal focus containment, initial focus, background `inert`/`aria-hidden`,
  dialog description wiring, native exit-link semantics, and focus restoration.
* Serve a registered WordPress logo through its responsive medium-size image
  markup instead of forcing the original full-size upload on every first visit.

== What's new in 2.0 ==
* Researcher type dropdown (required selection, stored with consent records)
* Collapsible numbered attestation section
* Form versioning — bumping the version re-gates all previously verified visitors
* Legal / FDA disclaimer box (503A/503B language) under the Enter button
* Business address + copyright footer lines
* Consent recording: timestamped log (form version, researcher type, IP, browser)
  with CSV export from the settings page — evidence for processor underwriting
* Kicker text, separate link/accent color, refined TrustedPeps-style card design

== Install ==
1. Deactivate + delete any previous version (settings are kept in the database).
2. Upload zip via Plugins → Add New → Upload Plugin, activate.
3. Configure at Settings → Access Gate.

== Notes ==
* Cache-safe: gate is hidden by JS when a valid cookie for the CURRENT form
  version exists. WP Rocket / Kinsta caches are purged automatically on save.
* "Skip for logged-in users" is on by default — test in an incognito window.
* The default legal text is a template modeled on common industry language.
  Have your payment processor or attorney review the final wording.

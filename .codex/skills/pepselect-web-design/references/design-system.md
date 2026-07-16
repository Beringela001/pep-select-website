# Design System Direction

## Visual character

Clinical-modern, clear, editorial, and approachable. The interface should feel designed around evidence and tasks, not decorated around products.

## Color direction

Start from the approved site identity and inspect active tokens before defining replacements.

Established anchors include:

- Deep navy: approximately `#0A1E40`
- Primary blue: approximately `#1A548F`
- Cyan or teal accents for navigation, information, and active research states
- Restrained green for approved, confirmed, or passed evidence
- Neutral blue-gray surfaces and borders

Do not treat approximate values as permission to replace the approved palette blindly. Extract and document exact production values during WEB-1 and WEB-2.

## Hierarchy

- Use one clear primary action per region.
- Group information by task and evidence.
- Avoid large empty areas that make pages feel unfinished.
- Avoid dense walls of equal-weight cards.
- Use typography, spacing, dividers, and restrained surfaces before adding decorative shapes.

## Components to standardize

- Announcement bar
- Desktop header
- Mobile header and drawer
- Footer and mobile accordions
- Buttons and text links
- Inputs, selects, checkboxes, radios, validation
- Product cards
- Evidence and COA cards
- Account navigation and dashboard cards
- Order and shipment cards
- Status badges
- Alerts
- Empty states
- Tables and mobile stacked records
- Carousels
- Modals and lightboxes
- Loading skeletons

## Status semantics

Never rely on color alone.

- Approved / confirmed / passed: green plus explicit text or icon
- Incoming / in testing: blue or teal plus stage text
- Waiting: neutral blue-gray plus exact waiting state
- Previous: muted treatment plus date/batch context
- Failed: restrained red plus explicit failed wording
- Not tested / unavailable: neutral and explicit, never implied passed

## Anti-patterns

- Purple AI gradients
- Neon glow
- Glassmorphism used as a default
- Excessive rounded pills
- Every section inside a card
- Tiny gray text
- Decorative laboratory imagery that competes with real documentation
- Generic stock-science visuals when product or process imagery is available
- Squeezed desktop navigation on mobile

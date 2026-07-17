# WEB-2C Homepage Copy Draft

## Approval status

This beta.2 product-first copy supersedes the visually rejected beta.1 compliance-led concept. It is implemented only in the private administrator preview and remains unpublished pending Paulo's visual approval.

## Section 1: Product-first hero

- Eyebrow: `PEP SELECT RESEARCH COMPOUNDS`
- Heading: `Choose the compound.` / `See the record.`
- Supporting copy: `A focused catalog of research compounds with clear product details, current pricing, and available batch documentation within reach.`
- Primary CTA: `Explore Compounds`
- Secondary CTA: `Review COAs`
- Supporting signals: `Live catalog pricing`; `Current stock visibility`; `Available batch records`; `Direct support`
- Dynamic product card: canonical product title, current price, current stock state, and `View Compound`

## Section 2: Confidence strip

- `Live catalog pricing`
- `Current stock visibility`
- `Available batch records`
- `Direct support`

## Section 3: Featured compounds

- Eyebrow: `CURRENTLY IN THE CATALOG`
- Heading: `Start with what you came for.`
- Supporting copy: `Browse current compounds with live pricing and availability.`
- Section CTA: `Explore All Compounds`
- Product CTA: `View Compound`
- Dynamic fields: canonical title, image, current price, current stock state, and product URL

## Section 4: Why Pep Select

- Eyebrow: `WHY PEP SELECT`
- Heading: `Less guessing.` / `More to go on.`
- Supporting copy: `We keep product details, batch status, and available documentation close to the compound—not buried behind broad promises.`
- `Focused catalog` — `A deliberate selection that is easier to review.`
- `Clear product details` — `Strength, availability, price, and documentation paths in one place.`
- `Records within reach` — `Open the Quality Archive when you want the batch-level detail.`

## Section 5: Batch identity

- Heading: `The vial should never be the whole story.`
- Supporting copy: `Compound. Strength. Batch. Cap. Crimp. Status. Read the identifiers together when the record is available.`
- CTA: `Review Batch Documentation`
- Explanatory labels: `Compound`; `Labeled Strength`; `Batch Number`; `Cap Color`; `Crimp Color`; `Current Status`
- Availability text: `Recorded when available`

## Section 6: Quality Archive

- Eyebrow: `PEP SELECT QUALITY ARCHIVE`
- Heading: `Match the vial.` / `Match the batch.`
- Supporting copy: `Search by compound, follow current and historical records, and open the full batch page for the details available on file.`
- Primary CTA: `Open the Quality Archive`
- Action labels: `Search by compound`; `Follow batch history`; `Open the full record`

This section does not display simulated records, laboratory results, statuses, or statistics. Detailed records remain in `/testing/`.

## Section 7: FAQ

- Heading: `Questions before you order?`
- `What are Pep Select compounds intended for?` — `Research use only.`
- `Do all products include COAs?` — `Where available, documentation is associated with individual batches.`
- `Can I verify a batch?` — `Yes. Use the Quality Archive to search by compound and open available batch records.`
- CTA: `Read All FAQs`

Source: the supported FAQ subset in `site-exports/elementor/saved-page-pepselect-homepage-571.json`, with the obsolete order-link item removed and the batch-search destination updated to the verified Quality Archive route.

## Section 8: Final CTA

- Heading: `Find the compound.` / `Keep the record close.`
- Supporting copy: `Explore the current catalog, or go directly to the Pep Select Quality Archive.`
- Primary CTA: `Explore Compounds`
- Secondary CTA: `Review COAs`

## Dynamic and compliance rules

- Never type product names, IDs, images, prices, stock values, or scarcity into homepage copy.
- Do not claim universal testing, guaranteed quality or purity, comparative pricing, human use, health outcomes, or administration guidance.
- Do not imply every product has a COA or every identifier exists for every record.
- Keep detailed COA data and public status terminology inside the Quality Archive until it exposes a supported homepage interface.
- Orbitrex remains a high-level commercial-design benchmark only; none of its copy or branded material is used.

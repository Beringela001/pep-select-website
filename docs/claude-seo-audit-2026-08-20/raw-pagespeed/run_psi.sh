#!/bin/bash
set -uo pipefail
cd "C:\Users\paulo\.claude\plugins\marketplaces\AgriciDaniel-claude-seo"
OUT="C:\Users\paulo\Documents\Pep Select Website\docs\claude-seo-audit-2026-08-20\raw-pagespeed"

declare -A URLS=(
  [home]="https://pepselect.com/"
  [shop]="https://pepselect.com/shop/"
  [nad]="https://pepselect.com/product/nad/"
  [testing]="https://pepselect.com/testing/"
  [coa-report]="https://pepselect.com/testing/nad-500-mg/nd50026205jp/"
)

run_one () {
  local slug="$1" url="$2" strategy="$3" run="$4"
  local outfile="$OUT/${slug}-${strategy}-run${run}.json"
  echo "START $slug $strategy run$run -> $url"
  uv run --with requests python scripts/pagespeed_check.py "$url" --strategy "$strategy" --psi-only --json > "$outfile" 2> "$OUT/${slug}-${strategy}-run${run}.err"
  echo "DONE  $slug $strategy run$run (exit $?)"
}

for slug in "${!URLS[@]}"; do
  url="${URLS[$slug]}"
  run_one "$slug" "$url" desktop 1
  run_one "$slug" "$url" mobile 1
  run_one "$slug" "$url" mobile 2
  run_one "$slug" "$url" mobile 3
done

echo "ALL PSI RUNS COMPLETE"

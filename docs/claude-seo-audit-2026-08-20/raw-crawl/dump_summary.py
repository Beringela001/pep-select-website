import json, os

base = r'C:\Users\paulo\Documents\Pep Select Website\docs\claude-seo-audit-2026-08-20\raw-crawl'
files = ['home','shop','product-nad','product-glp2t20','product-bacwater','compound-nad500','batch-nad500-jp','testing-hub','contact','faq','guide-coa']
for f in files:
    path = os.path.join(base, f + '-summary.json')
    d = json.load(open(path, encoding='utf-8'))
    sd = d.get('structured_data', {})
    print('===', f, '===')
    print('is_spa:', d.get('is_spa'), 'status:', d.get('status_code'), 'pub_date:', d.get('publication_date'))
    print(json.dumps(sd, indent=2)[:3000])
    print()

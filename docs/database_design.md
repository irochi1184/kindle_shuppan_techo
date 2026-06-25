# データベース設計

## books
書籍そのものを管理する。

- id
- title
- subtitle
- author_name
- target_reader
- book_goal
- reader_benefit
- description
- status
- created_at
- updated_at

## chapters
章を管理する。

- id
- book_id
- title
- summary
- body
- sort_order
- status
- word_count
- created_at
- updated_at

## chapter_versions
章の版を管理する。

- id
- chapter_id
- body
- note
- word_count
- created_at
- updated_at

## publishing_tasks
出版前チェックリストを管理する。

- id
- book_id
- title
- description
- is_done
- sort_order
- created_at
- updated_at

## kdp_metadata
KDP登録に必要な情報を管理する。

- id
- book_id
- description
- keywords_json
- categories_json
- price
- royalty_plan
- created_at
- updated_at


let g:ale_php_phpcs_standard="./settings/phpcs.xml"
let g:VimuxUseNearest=0

map <NORA>r :call VimuxRunCommand("./vendor/bin/phpunit --no-coverage  --color  --testdox ".bufname("%"))<CR>
map <SUBNORA>l :VimuxRunLastCommand<CR>

augroup templateGroup
  autocmd!
  autocmd BufNewFile *Test.php :0r "./share/vim-templates/test.php
  autocmd BufNewFile *.php :0r "./share/ivm-templates/class.php
augroup END

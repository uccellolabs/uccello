@extends('uccello::modules.default.tree.main')

@section('extra-meta')
<meta name="module-tree-default-url" content="{{ ucroute('uccello.tree.root', $domain, $module) }}">
<meta name="module-tree-children-url" content="{{ ucroute('uccello.tree.children', $domain, $module) }}">
<meta name="module-tree-open-all" content="{{ config('uccello.treeview.open_tree', true) && config('uccello.domains.open_tree', true) }}">
@append
<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;use App\Model\Account;
use App\Model\Rekening;
use Illuminate\Http\Request;


class AccountController extends Controller
{
    public function __construct() {
    	parent::__construct();
    }
    public function index()
    {
        $data['showDeleted'] = false;
        if ($this->request->get('show') == 'trash') {
            $data['showDeleted'] = true;
        }

        if ($data['showDeleted']) {
            $data['tableTitle'] = __('Show Trash');
            $data['accounts'] = $this->request->user()->deletedAccounts();
        } else {
            $data['tableTitle'] = __('Show Active Account');
            $data['accounts'] = $this->request->user()->accounts;
        }

        return view('master.account.index', $data);
    }
    public function create()
    {
        $currencies = config('currency');

        return view('account.create', [
            'currencies' => $currencies,
        ]);
    }

    public function store(Request $request)
    {
        $posted = $request->except(['_token', '_method']);
        
        $posted['user_id'] = $request->user()->id;

        $account = Account::create($posted);

        return redirect()->route('account.index');
    }

    public function edit(Account $account)
    {
        $currencies = config('currency');
        if ($account->user_id != $this->request->user()->id) {
            abort(403);
        }

        return view('master.account.edit', [
            'account'    => $account,
            'currencies' => $currencies,
        ]);
    }

    public function show(Account $account)
    {
        if ($account->user_id != $this->request->user()->id) {
            abort(403);
        }
        $transactions = $account->transaction();
        $searchQuery = $this->request->get('query');
        if (!is_null($searchQuery)) {
            $transactions = $transactions->where('description', 'LIKE', '%'.$searchQuery.'%');
        }
        $transactions = $transactions->orderBy('id', 'desc');

        return view('master.account.show', [
            'account'      => $account,
            'transactions' => $transactions->paginate(10),
        ]);
    }


    public function update(Account $account, Request $request)
    {
       
        $posted = $request->except(['_token', '_method']);
        
        $update = $account->update($posted);

        return redirect()->route('master.account.edit', $account->id);
    }

        public function transferStore(TransferRequest $request)
    {
        $posted = $this->request->all();

        $from = $this->request->user()->accounts()->where('id', $posted['from'])->first();
        $to = $this->request->user()->accounts()->where('id', $posted['to'])->first();

        $from->transfer(
            $to,
            $posted['amount'],
            $posted['category_id'],
            $posted['date'],
            $posted['description']
        );

        return redirect()->route('transaction.index');
    }
}

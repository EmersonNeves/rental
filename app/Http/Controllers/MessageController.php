<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewMessage;

class MessageController extends Controller
{
    public function send(Request $request)
    {
        \Log::info($request->all());
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'required',
        ]);

        $dados = $request->all();


        Mail::to($request->send_to)->send(new NewMessage($dados));

        return response()->json(['message' => 'Mensagem enviada com sucesso!']);
    }
}
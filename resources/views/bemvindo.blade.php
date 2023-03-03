Site da aplicação

@auth
 <h1>Usuário autenticado</h1>  
 <p>ID -> {{Auth::user()->id}}</p> 
 <p>Nome ->{{Auth::user()->name}}</p>
 <p>E-amil ->{{Auth::user()->email}}</p>  
@endauth

@guest
    Olá Visitante
@endguest
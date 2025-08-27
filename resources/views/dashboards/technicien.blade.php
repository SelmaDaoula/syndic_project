<h1>Bienvenue technicien</h1>

<p>Ici tu verras tes appartements, tes paiements, etc...</p>
<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit">Se déconnecter</button>
</form>

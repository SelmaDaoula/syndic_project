<h1>Bienvenue syndic</h1>

<p>Ici tu verras tes appartements, tes paiements, etc...</p>
<form action="{{ route('user.logout') }}" method="POST">
    @csrf
    <button type="submit">Se déconnecter</button>
</form>

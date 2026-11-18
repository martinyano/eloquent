<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eloquent Relationships Demo</title>
</head>
<body>
  <main>

    @if (session('status'))
      <p role="status" aria-live="polite">{{ session('status') }}</p>
    @endif

    <a href="{{ route('logout') }}" class="btn btn-danger btn-sm">Logout</a>

    <section id="one-to-one">
      <h2>One to One</h2>

      <form id="oneToOneForm" method="POST" action="{{ route('eloquent.one-to-one.store') }}" autocomplete="off">
        @csrf
        <div>
          <label for="otoName">User name</label><br>
          <input type="text" id="otoName" name="oto_name" value="{{ old('oto_name') }}">
          @error('oto_name')
            <p role="alert">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label for="otoHouse">House address</label><br>
          <input type="text" id="otoHouse" name="oto_address" value="{{ old('oto_address') }}">
          @error('oto_address')
            <p role="alert">{{ $message }}</p>
          @enderror
        </div>
        <button type="submit">Add user and house</button>
      </form>

      <table id="oneToOneTable" border="1" aria-label="Users and their one house">
        <thead>
          <tr>
            <th scope="col">User</th>
            <th scope="col">House</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($oneToOneUsers as $user)
            <tr>
              <td>{{ $user->name }}</td>
              <td>{{ $user->house?->address }}</td>
            </tr>
          @empty
            <tr><td colspan="2">No users yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </section>

    <hr>

    <section id="one-to-many">
      <h2>One to Many</h2>

      <form id="oneToManyForm" method="POST" action="{{ route('eloquent.one-to-many.store') }}" autocomplete="off">
        @csrf
        <div>
          <label for="otmName">User name</label><br>
          <input type="text" id="otmName" name="otm_name" list="otmUserList" value="{{ old('otm_name') }}">
          <datalist id="otmUserList">
            @foreach ($users as $user)
              <option value="{{ $user->name }}"></option>
            @endforeach
          </datalist>
          @error('otm_name')
            <p role="alert">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label for="otmAddress">House address</label><br>
          <input type="text" id="otmAddress" name="otm_address" value="{{ old('otm_address') }}">
          @error('otm_address')
            <p role="alert">{{ $message }}</p>
          @enderror
        </div>
        <button type="submit">Add house</button>
      </form>

      <table id="oneToManyTable" border="1" aria-label="Users and their many houses">
        <thead>
          <tr>
            <th scope="col">User</th>
            <th scope="col">Houses</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($oneToManyUsers as $user)
            <tr>
              <td>{{ $user->name }}</td>
              <td>
                @if ($user->houses->isNotEmpty())
                  {{ $user->houses->pluck('address')->join(', ') }}
                @else
                  No houses yet
                @endif
              </td>
            </tr>
          @empty
            <tr><td colspan="2">No users yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </section>

    <hr>

    <section id="many-to-many">
      <h2>Many to Many</h2>

      <form id="manyToManyForm" method="POST" action="{{ route('eloquent.many-to-many.store') }}" autocomplete="off">
        @csrf
        <div>
          <label for="mtmName">User name</label><br>
          <input type="text" id="mtmName" name="mtm_name" value="{{ old('mtm_name') }}">
          @error('mtm_name')
            <p role="alert">{{ $message }}</p>
          @enderror
        </div>
        <fieldset>
          <legend>Groups</legend>
          @foreach ($groups as $group)
            <label>
              <input
                type="checkbox"
                name="mtm_groups[]"
                value="{{ $group->name }}"
                @checked(in_array($group->name, (array) old('mtm_groups', []), true))
              >
              {{ $group->name }}
            </label>
          @endforeach
          @error('mtm_groups')
            <p role="alert">{{ $message }}</p>
          @enderror
        </fieldset>
        <button type="submit">Add to groups</button>
      </form>

      <table id="manyToManyTable" border="1" aria-label="Users and their groups">
        <thead>
          <tr>
            <th scope="col">User</th>
            <th scope="col">Groups</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($manyToManyUsers as $user)
            <tr>
              <td>{{ $user->name }}</td>
              <td>{{ $user->groups->pluck('name')->join(', ') }}</td>
            </tr>
          @empty
            <tr><td colspan="2">No users yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </section>

  </main>
</body>
</html>

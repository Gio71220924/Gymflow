<?php

namespace App\Http\Middleware;

use Closure;

class EnsureMemberProfileCompleted
{
    /**
     * Handle an incoming request.
     *
     * Jika user role "user" belum memiliki profil member, paksa ke halaman
     * lengkapi profil terlebih dahulu dengan pesan peringatan.
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->role === \App\User::ROLE_USER && ! $user->memberGym) {
            if (! $request->routeIs('member.profile.setup', 'member.profile.save', 'logout')) {
                return redirect()
                    ->route('member.profile.setup')
                    ->with('error', 'Mohon lengkapi profil anda terlebih dahulu!');
            }
        }

        return $next($request);
    }
}

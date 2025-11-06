<!DOCTYPE html>

<html class="dark" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Gym Membership Management</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "primary": "#66D7D1",
              "secondary": "#FC7753",
              "background-light": "#F2EFEA",
              "background-dark": "#070707",
              "card-dark": "#28231C",
              "text-light": "#F2EFEA",
              "text-dark": "#070707",
            },
            fontFamily: {
              "display": ["Lexend", "sans-serif"]
            },
            borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
          },
        },
      }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            font-size: 20px;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-text-dark dark:text-text-light">
<div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
<div class="layout-container flex h-full grow flex-col">
<div class="px-4 sm:px-8 md:px-16 lg:px-24 xl:px-40 flex flex-1 justify-center py-5">
<div class="layout-content-container flex flex-col max-w-[960px] flex-1">
<!-- TopNavBar -->
<header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-card-dark px-4 sm:px-6 lg:px-10 py-3">
<div class="flex items-center gap-4 text-text-light">
<div class="size-6 text-primary">
<svg fill="none" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
<g clip-path="url(#clip0_6_330)">
<path clip-rule="evenodd" d="M24 0.757355L47.2426 24L24 47.2426L0.757355 24L24 0.757355ZM21 35.7574V12.2426L9.24264 24L21 35.7574Z" fill="currentColor" fill-rule="evenodd"></path>
</g>
<defs>
<clippath id="clip0_6_330">
<rect fill="white" height="48" width="48"></rect>
</clippath>
</defs>
</svg>
</div>
<h2 class="text-text-light text-lg font-bold leading-tight tracking-[-0.015em]">GymLogo</h2>
</div>
<div class="flex flex-1 justify-end items-center gap-4">
<!-- SegmentedButtons (View Toggle) -->
<div class="flex h-10 w-48 items-center justify-center rounded-lg bg-card-dark p-1">
<label class="flex cursor-pointer h-full grow items-center justify-center overflow-hidden rounded-lg px-2 has-[:checked]:bg-background-dark has-[:checked]:shadow-[0_0_4px_rgba(0,0,0,0.1)] has-[:checked]:text-text-light text-text-light/70 text-sm font-medium leading-normal">
<span class="truncate">Admin</span>
<input checked="" class="invisible w-0" name="view-toggle" type="radio" value="Admin View"/>
</label>
<label class="flex cursor-pointer h-full grow items-center justify-center overflow-hidden rounded-lg px-2 has-[:checked]:bg-background-dark has-[:checked]:shadow-[0_0_4px_rgba(0,0,0,0.1)] has-[:checked]:text-text-light text-text-light/70 text-sm font-medium leading-normal">
<span class="truncate">User</span>
<input class="invisible w-0" name="view-toggle" type="radio" value="User View"/>
</label>
</div>
<button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-text-dark text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors">
<span class="truncate">Add Member</span>
</button>
<div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" data-alt="User profile avatar" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCdYOZUIUEHupKNhRGA1Iv-8tHZYhPUEH34IG6UrBrO91u0ijxdKl39ZDmlG7X86yEI5hTnnyw_XSX_UlDxGlU3WI514DS0NEMjNYPF9XiG7crm60hf7eQGqaBpWxOCz_TqR9KY7_1z7RWR7SJJyl4orN2Nd3w13XpLYyR62QQsvzTjAxoGySYVuM8I8ccZOxRf4hEKdkpYLyKnrHSI8Suz6q0A9hBb8GtjN8yzYj_aL3lV9o4O5OSbD_ya4tsqxNXC_2S0uYX29vg");'></div>
</div>
</header>
<main class="flex flex-col gap-6 mt-8">
<!-- Admin View Content -->
<div class="flex flex-col gap-4" id="admin-view">
<!-- PageHeading -->
<div class="flex flex-wrap justify-between gap-3 p-4">
<div class="flex min-w-72 flex-col gap-3">
<p class="text-text-light text-4xl font-black leading-tight tracking-[-0.033em]">Member Management</p>
<p class="text-text-light/60 text-base font-normal leading-normal">Create, read, update, and delete member information.</p>
</div>
</div>
<!-- SearchBar -->
<div class="px-4 py-3">
<label class="flex flex-col min-w-40 h-12 w-full">
<div class="flex w-full flex-1 items-stretch rounded-lg h-full">
<div class="text-text-light/60 flex border-none bg-card-dark items-center justify-center pl-4 rounded-l-lg border-r-0">
<span class="material-symbols-outlined">search</span>
</div>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-light focus:outline-0 focus:ring-2 focus:ring-primary/50 border-none bg-card-dark focus:border-none h-full placeholder:text-text-light/60 px-4 rounded-l-none border-l-0 pl-2 text-base font-normal leading-normal" placeholder="Search members by name or email" value=""/>
</div>
</label>
</div>
<!-- Table -->
<div class="px-4 py-3 @container">
<div class="flex overflow-hidden rounded-lg border border-card-dark bg-background-dark">
<table class="w-full flex-1">
<thead>
<tr class="bg-card-dark/50">
<th class="px-4 py-3 text-left text-text-light text-sm font-medium leading-normal">Member Name</th>
<th class="px-4 py-3 text-left text-text-light text-sm font-medium leading-normal">Email</th>
<th class="px-4 py-3 text-left text-text-light text-sm font-medium leading-normal">Membership</th>
<th class="px-4 py-3 text-left text-text-light text-sm font-medium leading-normal">Status</th>
<th class="px-4 py-3 text-left text-text-light text-sm font-medium leading-normal">Actions</th>
</tr>
</thead>
<tbody>
<tr class="border-t border-t-card-dark hover:bg-card-dark/30 transition-colors">
<td class="h-[72px] px-4 py-2 text-text-light text-sm font-normal leading-normal">Alex Johnson</td>
<td class="h-[72px] px-4 py-2 text-text-light/70 text-sm font-normal leading-normal">alex.j@email.com</td>
<td class="h-[72px] px-4 py-2 text-text-light/70 text-sm font-normal leading-normal">Premium</td>
<td class="h-[72px] px-4 py-2 text-sm font-normal leading-normal">
<span class="inline-flex items-center justify-center rounded-full h-6 px-3 bg-primary/20 text-primary text-xs font-medium">Active</span>
</td>
<td class="h-[72px] px-4 py-2 text-text-light/70 text-sm font-bold leading-normal tracking-[0.015em]">
<div class="flex gap-2">
<button class="p-2 rounded-md hover:bg-card-dark"><span class="material-symbols-outlined text-text-light/80">edit</span></button>
<button class="p-2 rounded-md hover:bg-card-dark"><span class="material-symbols-outlined text-secondary">delete</span></button>
</div>
</td>
</tr>
<tr class="border-t border-t-card-dark hover:bg-card-dark/30 transition-colors">
<td class="h-[72px] px-4 py-2 text-text-light text-sm font-normal leading-normal">Maria Garcia</td>
<td class="h-[72px] px-4 py-2 text-text-light/70 text-sm font-normal leading-normal">maria.g@email.com</td>
<td class="h-[72px] px-4 py-2 text-text-light/70 text-sm font-normal leading-normal">Basic</td>
<td class="h-[72px] px-4 py-2 text-sm font-normal leading-normal">
<span class="inline-flex items-center justify-center rounded-full h-6 px-3 bg-primary/20 text-primary text-xs font-medium">Active</span>
</td>
<td class="h-[72px] px-4 py-2 text-text-light/70 text-sm font-bold leading-normal tracking-[0.015em]">
<div class="flex gap-2">
<button class="p-2 rounded-md hover:bg-card-dark"><span class="material-symbols-outlined text-text-light/80">edit</span></button>
<button class="p-2 rounded-md hover:bg-card-dark"><span class="material-symbols-outlined text-secondary">delete</span></button>
</div>
</td>
</tr>
<tr class="border-t border-t-card-dark hover:bg-card-dark/30 transition-colors">
<td class="h-[72px] px-4 py-2 text-text-light text-sm font-normal leading-normal">Sam Chen</td>
<td class="h-[72px] px-4 py-2 text-text-light/70 text-sm font-normal leading-normal">sam.c@email.com</td>
<td class="h-[72px] px-4 py-2 text-text-light/70 text-sm font-normal leading-normal">Premium</td>
<td class="h-[72px] px-4 py-2 text-sm font-normal leading-normal">
<span class="inline-flex items-center justify-center rounded-full h-6 px-3 bg-secondary/20 text-secondary text-xs font-medium">Inactive</span>
</td>
<td class="h-[72px] px-4 py-2 text-text-light/70 text-sm font-bold leading-normal tracking-[0.015em]">
<div class="flex gap-2">
<button class="p-2 rounded-md hover:bg-card-dark"><span class="material-symbols-outlined text-text-light/80">edit</span></button>
<button class="p-2 rounded-md hover:bg-card-dark"><span class="material-symbols-outlined text-secondary">delete</span></button>
</div>
</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
<!-- User View Content (Initially hidden, can be toggled with JS) -->
<div class="hidden flex flex-col gap-4" id="user-view">
<!-- PageHeading -->
<div class="flex flex-wrap justify-center text-center gap-3 p-4">
<div class="flex w-full flex-col items-center gap-3">
<p class="text-text-light text-4xl font-black leading-tight tracking-[-0.033em]">Choose Your Plan</p>
<p class="text-text-light/60 text-base font-normal leading-normal max-w-md">Select the perfect membership plan to start your fitness journey with us today.</p>
</div>
</div>
<!-- Plan Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-4">
<!-- Basic Plan Card -->
<div class="flex flex-col rounded-xl bg-card-dark border border-card-dark/50 p-8">
<h3 class="text-2xl font-bold text-text-light">Basic Fit</h3>
<p class="mt-2 text-text-light/60">Core features for a great start.</p>
<div class="mt-6">
<span class="text-5xl font-black text-text-light">$29</span>
<span class="text-lg font-medium text-text-light/60">/month</span>
</div>
<ul class="mt-8 space-y-4 text-text-light/80">
<li class="flex items-center gap-3">
<span class="material-symbols-outlined text-primary">check_circle</span>
<span>Full Gym Access</span>
</li>
<li class="flex items-center gap-3">
<span class="material-symbols-outlined text-primary">check_circle</span>
<span>Standard Group Classes</span>
</li>
<li class="flex items-center gap-3">
<span class="material-symbols-outlined text-primary">check_circle</span>
<span>Locker Room Access</span>
</li>
</ul>
<button class="mt-10 flex w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-primary/20 text-primary text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/30 transition-colors">
<span class="truncate">Select Plan</span>
</button>
</div>
<!-- Premium Plan Card -->
<div class="flex flex-col rounded-xl bg-card-dark border border-primary p-8 relative overflow-hidden">
<div class="absolute top-0 right-0 text-xs font-bold bg-primary text-text-dark px-4 py-1.5 rounded-bl-lg">Most Popular</div>
<h3 class="text-2xl font-bold text-text-light">Premium Pro</h3>
<p class="mt-2 text-text-light/60">All features for the ultimate experience.</p>
<div class="mt-6">
<span class="text-5xl font-black text-text-light">$59</span>
<span class="text-lg font-medium text-text-light/60">/month</span>
</div>
<ul class="mt-8 space-y-4 text-text-light/80">
<li class="flex items-center gap-3">
<span class="material-symbols-outlined text-primary">check_circle</span>
<span>Everything in Basic</span>
</li>
<li class="flex items-center gap-3">
<span class="material-symbols-outlined text-primary">check_circle</span>
<span>Personal Trainer Sessions (2/mo)</span>
</li>
<li class="flex items-center gap-3">
<span class="material-symbols-outlined text-primary">check_circle</span>
<span>Sauna and Spa Access</span>
</li>
<li class="flex items-center gap-3">
<span class="material-symbols-outlined text-primary">check_circle</span>
<span>Guest Passes</span>
</li>
</ul>
<button class="mt-10 flex w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-primary text-text-dark text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors">
<span class="truncate">Book Now</span>
</button>
</div>
</div>
</div>
</main>
</div>
</div>
</div>
</div>
</body></html>
<div 
    class="relative flex justify-center items-center text-center h-screen max-h-screen overflow-hidden"
    x-data="lopi()"
    wire:poll.1000ms="poll"
> 
    <div
        class="absolute top-0 left-0 w-screen h-screen z-10 pointer-events-none"
        x-ref="overlay"
        wire:ignore
    >
    </div>
    <div>
        <img class="mx-auto w-[200px]" src="{{ Vite::asset('resources/lopi_assets/lopibig.png') }}"></img>
        <h1 class="mt-[10px] mb-[5px] text-4xl font-bold">Enjoy some Lopi! <span class="text-[#561378] text-sm">(actually Jim)</span></h1>
        
        <div wire:ingore x-text="client.userClicks" class="text-[#444] text-[24px] font-bold mt-[10px] font-['Trebuchet_MS']"></div>

        <button
            @keydown.enter.prevent="null"
            @keydown.space.prevent="null"
            x-on:click="click"
            class="relative z-50 inline-block w-full text-[15px] bg-[#ffadd2] text-white font-bold py-[10px] px-[20px] border border-white hover:bg-opacity-80 hover:shadow-[#ffadd2] active:shadow-[#ffadd2] hover:shadow-md active:shadow-2xl active:bg-white active:text-[#ffadd2] active:border-[#ffadd2] shadow-md"
        >
            I'm Lopi
        </button>
        
        @auth
            <div class="m-[20px] text-[16px] text-black font-bold group">
                Welcome back, <span class="text-lopi-purple-900">{{ Auth::user()->name }}</span>!
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="font-semibold text-lopi-purple-900 hover:text-lopi-purple-400 text-xs"
                    >Logout</button>
                </form>
            </div>
            <div class="m-[20px] text-[16px] text-black font-bold group">
                You are in {{ $rank }} place
            </div>
        @endauth
        <div class="m-[20px] text-[16px] text-black font-bold group">
            <a href="https://www.youtube.com/@Punkalopi" target="_blank">
                Punkalopi's Youtube Channel
                <span class="text-[#561378] text-sm group-hover:text-purple-500/80">(check it out, it's cool!)</span>
            </a>
        </div>
        <div class="m-[20px] text-[16px] text-black font-bold group">
            <a href="https://twitter.com/Punkalopi" target="_blank">
                Punkalopi's Twitter
                <span class="text-[#561378] text-sm group-hover:text-purple-500/80">(silly bird app, very fun!)</span>
            </a>
        </div>
        <div class="mt-[20px] text-lg font-['Impact']">
            <p>TOTAL CLICKER COUNT</p>
            <p class="text-4xl" x-text="client.totalClicks"></p>
        </div>
        <div class="mt-[20px] text-[15px] text-black font-bold group">
            <a href="{{ route('leaderboard') }}" class="text-[#561378]  group-hover:text-purple-500/80">
                Leader Boarder
            </a>
        </div>
        @guest
            <div class="text-[16px] text-black font-bold">
                <a href="{{ route('login') }}" class="text-[#561378] hover:text-purple-500/80">Login</a>
                or
                <a href="{{ route('register') }}" class="text-[#561378] hover:text-purple-500/80">Register<a/>
                to track your Lopi count!
            </div>
        @endguest
        <livewire:high-score-board />
    </div>

</div>

@pushOnce('scripts')
    <script>
        let lopi = () => {
            return {
                totalClicksInterval: null,
                countInterval: null,
                countDelay: 1000 / 60,
                pullDelay: 1000,
                lastTime: 0,
                client: {
                    userClicks: 0,
                    totalClicks: 0,
                    rank: null,
                },
                server: {
                    userClicks: @entangle('userClicks'),
                    totalClicks: @entangle('totalClicks'),
                    rank: @entangle('rank'),
                },
                init() {
                    requestAnimationFrame(this.animate.bind(this))
                },
                animate(timeStamp) {
                    this.animateCounts()
                    this.lopis.forEach((lopi) => {
                        lopi.animate(timeStamp)
                    })
                    this.lopis = this.lopis.filter((lopi) => !lopi.shouldDelete)
                    requestAnimationFrame(this.animate.bind(this))
                },
                animateCounts() {
                    ['userClicks', 'totalClicks'].forEach((key) => {
                        if (this.client[key] > this.server[key]) {
                            this.client[key] -= Math.max(1, Math.floor((this.client[key] - this.server[key]) / 10))
                        } else if (this.client[key] < this.server[key]) {
                            this.client[key] += Math.max(1, Math.floor((this.server[key] - this.client[key]) / 10))
                        }
                    })
                },
                isGuest: @js(auth()),
                lopis: [],
                click() {
                    this.$wire.click()
                    this.lopis.push(new Lopi().make(this.$refs.overlay))
                }
            }
        }

        class Lopi {
            sounds = [
                new Audio("{{ Vite::asset('resources/lopi_assets/1.mp3') }}"),
                new Audio("{{ Vite::asset('resources/lopi_assets/2.mp3') }}"),
                new Audio("{{ Vite::asset('resources/lopi_assets/3.mp3') }}"),
            ];
            image = "{{ Vite::asset('resources/lopi_assets/lopi.png') }}";
            element = null;
            createTime = null;
            top = Math.floor(Math.random() * (window.innerHeight - 200)) + 'px';
            left = Math.floor(Math.random() * (window.innerWidth - 200)) + 'px';
            shouldDelete = false;
            // between 1 and 5 seconds
            fadeTime = Math.floor(Math.random() * 4000) + 1000;
            faded = false
            make(overlay) {
                this.sounds[Math.floor(Math.random()*(this.sounds.length))].cloneNode().play()
                this.element = document.createElement('img')
                this.element.src = this.image

                this.element.classList.add(
                    'absolute', 'z-20', 'transition-all',
                    'duration-[1500ms]', 'ease-in-out', 'opacity-0', 'transform', 
                    'scale-25', 'w-[250px]', 'h-auto',
                    'pointer-events-none', 'animate-bounce'
                )
                this.element.style.top = this.top
                this.element.style.left = this.left
                overlay.appendChild(this.element)
                return this
            }

            popup() {
                this.element.classList.remove('opacity-0', 'scale-25')
                this.element.classList.add('opacity-100', 'scale-100')
            }

            fade() {
                this.faded = true
                this.element.classList.remove('opacity-100', 'scale-100')
                this.element.classList.add('opacity-0', 'scale-25')
            }

            remove() {
                this.element.remove()
                this.shouldDelete = true
            }

            animate(timeStamp) {
                if (this.createTime === null) {
                    this.createTime = timeStamp
                    this.popup()
                }

                if (timeStamp - this.createTime > this.fadeTime && !this.faded) {
                    this.fade()
                }

                if (timeStamp - this.createTime > this.fadeTime + 1500) {
                    this.remove()
                }
            }
        }
    </script>
@endPushOnce
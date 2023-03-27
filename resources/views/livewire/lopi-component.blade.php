<div 
    class="flex justify-center items-center text-center mt-12"
    x-data="lopi()"
    wire:poll.1000ms="poll"
> 
    <div
        class="flex justify-center items-center fixed top-0 left-0 w-screen h-screen z-10 pointer-events-none"
        x-ref="overlay"
        wire:ignore
    >
        <div 
            x-cloak
            x-show="popup.show"
            class="px-6 py-4 relative w-2/6 h-1/8 bg-lopi-purple-50 rounded-xl z-50"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 transform scale-0"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-500"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-0"
        >
            <div class="absolute top-0 left-0 w-full h-2 bg-lopi-purple-500 rounded-tl-xl transition-all duration-75" :style="{ width: progressBar }"></div>
            <div class="text-3xl text-left text-lopi-purple-500 rounded-lg">Warning!</div>
            <p class="mt-2">The space key and enter key are disabled on this button.✌️</p>
            {{-- progress bar until message vanishses --}}
            <div class="absolute block bottom-0 left-0 w-full h-2 bg-lopi-purple-500 rounded-bl-xl transition-all duration-75" 
                :style="{ width: progressBar }"
            ></div>
        </div>
    </div>
    <div>
        <img class="mx-auto w-[200px]" src="{{ Vite::asset('resources/lopi_assets/lopibig.png') }}"></img>
        <h1 class="mt-[10px] mb-[5px] text-4xl font-bold">Enjoy some Lopi! <span class="text-[#561378] text-sm">(actually Jim)</span></h1>
        
        <div wire:ingore x-text="client.userClicks" class="z-40 text-[#444] text-[24px] font-bold mt-[10px] font-['Trebuchet_MS'] relative"></div>

        <button
            @keydown.enter.prevent="showPopup"
            @keydown.space.prevent="showPopup"
            x-on:click="click"
            class="relative z-40 inline-block w-full text-[15px] bg-[#ffadd2] text-white font-bold py-[10px] px-[20px] border border-white hover:bg-opacity-80 hover:shadow-[#ffadd2] active:shadow-[#ffadd2] hover:shadow-md active:shadow-2xl active:bg-white active:text-[#ffadd2] active:border-[#ffadd2] shadow-md"
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
        <livewire:high-score-board maxRank="10" />
    </div>

</div>

@pushOnce('scripts')
    <script>
        let lopi = () => {
            return {
                popup: {
                    show: false,
                    at: null,
                },
                progressBar: null,
                countDelay: 1000 / 60,
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

                    // start timer when popup is shown and turn off after 5 seconds
                    if (this.popup.show) {
                        this.progressBar = `${100 - ((timeStamp - this.popup.at) / 50)}%`
                        if (!this.popup.at) {
                            this.popup.at = timeStamp
                        } else if (timeStamp - this.popup.at > 5000) {
                            this.popup.show = false
                            this.popup.at = null
                        }
                    }

                    this.lopis.forEach((lopi) => {
                        lopi.animate(timeStamp)
                    })
                    this.lopis = this.lopis.filter((lopi) => !lopi.shouldDelete)
                    requestAnimationFrame(this.animate.bind(this))
                },
                animateCounts() {
                    ['userClicks', 'totalClicks']
                        .forEach((key) => {
                            if (this.client[key] > this.server[key]) {
                                this.client[key] -= Math.max(1, Math.floor((this.client[key] - this.server[key]) / 10))
                            } else if (this.client[key] < this.server[key]) {
                                this.client[key] += Math.max(1, Math.floor((this.server[key] - this.client[key]) / 10))
                            }
                        })
                },
                showPopup() {
                    this.popup.show = true
                    this.popup.at = null
                },
                isGuest: @js(auth()),
                lopis: [],
                click() {
                    this.$wire.click()
                    this.lopis.push(new Lopi().make(this.$refs.overlay))
                }
            }
        }

        let preloadedSounds = [
            new Audio("{{ Vite::asset('resources/lopi_assets/1.mp3') }}"),
            new Audio("{{ Vite::asset('resources/lopi_assets/2.mp3') }}"),
            new Audio("{{ Vite::asset('resources/lopi_assets/3.mp3') }}"),
        ]
        preloadedSounds.forEach((sound) => {
            sound.load()
        })

        class Lopi {
            // get random sound from preloaded sound
            sound = preloadedSounds[Math.floor(Math.random()*(preloadedSounds.length))].cloneNode(true)
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
                this.sound.playbackRate = 1.00
                this.sound.play()
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
<?php

namespace Uccello\Core\Http\Controllers\Core;

use Uccello\Core\Models\Domain;

class DomainsTreeController
{
    /**
     * Returns all roots domains where the user can access
     *
     * @return array
     */
    public function root(?Domain $domain)
    {
        // If we don't use multi domains, find the first one
        if (!uccello()->useMultiDomains()) {
            $domain = Domain::firstOrFail();
        }

        $rootDomains = app('uccello')->getRootDomains();

        $domains = [];
        foreach ($rootDomains as $_domain) {
            $formattedDomain = $this->getFormattedDomainToAdd($domain, $_domain);
            if ($formattedDomain) {
                $domains[] = $formattedDomain;
            }
        }

        return $domains;
    }

    /**
     * Returns all domain's children where the user can access
     *
     * @return array
     */
    public function children(?Domain $domain)
    {
        // If we don't use multi domains, find the first one
        if (!uccello()->useMultiDomains()) {
            $domain = Domain::firstOrFail();
        }

        $parentDomain = Domain::find(request('id'));

        $domains = [];
        if ($parentDomain) {
            foreach ($parentDomain->children()->orderBy('name')->get() as $_domain) {
                $formattedDomain = $this->getFormattedDomainToAdd($domain, $_domain);
                if ($formattedDomain) {
                    $domains[] = $formattedDomain;
                }
            }
        }

        return $domains;
    }

    /**
     * Get formatted domain to add to the tree
     *
     * @param \Uccello\Core\Models\Domain $currentDomain
     * @param \Uccello\Core\Models\Domain $domain
     * @return array|null
     */
    protected function getFormattedDomainToAdd(Domain $currentDomain, Domain $domain)
    {
        $formattedDomain = null;

        $hasRoleOnDomain = auth()->user()->hasRoleOnDomain($domain);
        $hasRoleOnDescendantDomain = auth()->user()->hasRoleOnDescendantDomain($domain);

        if ($hasRoleOnDomain || $hasRoleOnDescendantDomain) {
            $formattedDomain = [
                "id" => $domain->id,
                "text" => $domain->name,
                "children" => $domain->children->count() > 0,
                "a_attr" => [
                    "href" => $hasRoleOnDomain && $domain->id !== $currentDomain->id ? ucroute('uccello.home', $domain) : '#',
                    "class" => !$hasRoleOnDomain ? 'disabled' : $domain->id === $currentDomain->id ? 'current' : ''
                ]
            ];
        }

        return $formattedDomain;
    }
}
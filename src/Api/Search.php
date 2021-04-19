<?php

declare(strict_types=1);

/*
 * This file is part of the Gitlab API library.
 *
 * (c) Matt Humphrey <matth@windsor-telecom.co.uk>
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gitlab\Api;

use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Search extends AbstractApi
{
    /**
     * @param array $parameters {
     *
     *     @var bool               $archived                    limit by archived status
     *     @var string             $visibility                  limit by visibility public, internal, or private
     *     @var string             $order_by                    Return projects ordered by id, name, path, created_at, updated_at,
     *                                                          last_activity_at, repository_size, storage_size, packages_size or
     *                                                          wiki_size fields (default is created_at)
     *     @var string             $sort                        Return projects sorted in asc or desc order (default is desc)
     *     @var string             $search                      return list of projects matching the search criteria
     *     @var bool               $search_namespaces           Include ancestor namespaces when matching search criteria
     *     @var bool               $simple                      return only the ID, URL, name, and path of each project
     *     @var bool               $owned                       limit by projects owned by the current user
     *     @var bool               $membership                  limit by projects that the current user is a member of
     *     @var bool               $starred                     limit by projects starred by the current user
     *     @var bool               $statistics                  include project statistics
     *     @var bool               $with_issues_enabled         limit by enabled issues feature
     *     @var bool               $with_merge_requests_enabled limit by enabled merge requests feature
     *     @var int                $min_access_level            Limit by current user minimal access level
     *     @var int                $id_after                    Limit by project id's greater than the specified id
     *     @var int                $id_before                   Limit by project id's less than the specified id
     *     @var \DateTimeInterface $last_activity_after         Limit by last_activity after specified time
     *     @var \DateTimeInterface $last_activity_before        Limit by last_activity before specified time
     *     @var bool               $repository_checksum_failed  Limit by failed repository checksum calculation
     *     @var string             $repository_storage          Limit by repository storage type
     *     @var bool               $wiki_checksum_failed        Limit by failed wiki checksum calculation
     *     @var bool               $with_custom_attributes      Include custom attributes in response
     *     @var string             $with_programming_language   Limit by programming language
     * }
     *
     * @throws UndefinedOptionsException If an option name is undefined
     * @throws InvalidOptionsException   If an option doesn't fulfill the
     *                                   specified validation rules
     *
     * @return mixed
     */
    public function all(array $parameters = [])
    {
        $resolver = $this->createOptionsResolver();
        $booleanNormalizer = function (Options $resolver, $value): string {
            return $value ? 'true' : 'false';
        };
        $resolver->setDefined('confidential')
            ->setAllowedTypes('confidential', 'bool')
            ->setNormalizer('confidential', $booleanNormalizer)
        ;
        $scope = [
            'projects',
            'issues',
            'merge_requests',
            'milestones',
            'snippet_titles',
            'users'
        ];
        $resolver->setDefined('scope')
            ->setAllowedValues('scope', $scope)
        ;
        $resolver->setDefined('search');
        $resolver->setDefined('order_by')
            ->setAllowedValues('order_by', ['created_at'])
        ;
        $resolver->setDefined('sort')
            ->setAllowedValues('sort', ['asc', 'desc'])
        ;
        return $this->get('search', $resolver->resolve($parameters));
    }
}
